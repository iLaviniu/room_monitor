#include <SPI.h>
#include "Types.h"
#include "Dht11Sensor.h"
#include "Bmp280Sensor.h"
#include "EthernetService.h"
#include "Arduino_LED_Matrix.h"

WiFiSSLClient client;
DHT dht(DHTPIN, DHTTYPE);
Adafruit_BMP280 bmp;

ArduinoLEDMatrix matrixOutput;

Dht11SensorValues dht11SensorValues;
Bmp280SensorValues bmp280SensorValues;

int status = WL_IDLE_STATUS;
char ssid[] = SECRET_SSID;
char pass[] = SECRET_PASS;

unsigned long periodMillisWifiTest = 15000UL; /*15 sec.*/
unsigned long timeNowWifiTest = 0;

unsigned long periodMillisStatusSending = 60000UL; /*1 min.*/
unsigned long timeNowStatusSending = 0;

unsigned long periodMillisDataSending = 180000UL; /*3 min.*/
unsigned long timeNowDataSending = 0;

unsigned long periodMillisOneHourRestart = 3600000UL; /*1 hour.*/
unsigned long timeNowOneHourRestart = 0;

unsigned long periodMinutesDataSending = ( (periodMillisDataSending / 1000) / 60 );

bool isSetupDone = false;
bool isWifiActive = false;
int wifiBeginCounter = 0;


void wifiConnectionTest() {
  int trial = 0;
  bool wifiStatus = false;

  for(trial = 0; ( (trial < 1) && (wifiStatus == false) ); trial++) {
    Serial.println("loop: wifi connection test trial " + String(trial + 1));
    if(WiFi.status() != WL_CONNECTED) {
      WiFi.disconnect();
      status = WiFi.begin(ssid, pass);
    }
    else
    {
      wifiStatus = true;
    }
  }

  if(WiFi.status() == WL_CONNECTED) {
    Serial.println("loop: wifi connection alive");
  }
  else
  {
    Serial.println("loop: wifi connection dead");
  }
}

void sendStatusToServer() {
  char * httpResponse;
  char * httpResponseStart;
  char buffer[50] = "no_response_from_server\0";
  byte tryIndex;
  bool sendRequested = true;

  for(tryIndex = 0; ((tryIndex < 5) && (sendRequested == true)); tryIndex++) {
    httpResponse = (char*)malloc(35 * sizeof(char));
    httpResponseStart = httpResponse;
    sendStatusHttpRequest(&client, httpResponse);

    copyMemmoryToBuffer(httpResponseStart, &buffer[0]);
    
    if(buffer[0] == 'O' && buffer[1] == 'K') {
      sendRequested = false;
      Serial.print("trial: ");
      Serial.print(tryIndex);
      Serial.print(" ");
      Serial.println(buffer);
    } else {
      Serial.print("trial: ");
      Serial.print(tryIndex);
      Serial.print(" ");
      Serial.println(buffer);
    }

    /*free allocated space*/
    free(httpResponse);
  }
}

void sendDataToServer() {
  char * httpResponse;
  char * httpResponseStart;
  char buffer[50] = "no_response_from_server\0";
  byte tryIndex;
  bool sendRequested = true;

  /*reading, updating DHT 11 sensor*/
  dht11SensorValues = updateDht11Values(dht11SensorValues, dht);

  /*reading, updating BMP 280 sensor*/
  bmp280SensorValues = updateBmp280Values(bmp280SensorValues, bmp);

  for(tryIndex = 0; ((tryIndex < 5) && (sendRequested == true)); tryIndex++) {
    httpResponse = (char*)malloc(35 * sizeof(char));
    httpResponseStart = httpResponse;
    sendDataHttpRequest(&client, httpResponse, dht11SensorValues, bmp280SensorValues, periodMinutesDataSending);

    copyMemmoryToBuffer(httpResponseStart, &buffer[0]);
    
    if(buffer[0] == 'O' && buffer[1] == 'K') {
      sendRequested = false;
      Serial.print("trial: ");
      Serial.print(tryIndex);
      Serial.print(" ");
      Serial.println(buffer);
    } else {
      Serial.print("trial: ");
      Serial.print(tryIndex);
      Serial.print(" ");
      Serial.println(buffer);
    }

    /*free allocated space*/
    free(httpResponse);
  }

  /*free allocated memory inside struct dht11SensorValues*/
  free(dht11SensorValues.celsius);
  free(dht11SensorValues.fahrenheit);
  free(dht11SensorValues.humidity);

  /*free allocated memory inside struct bmp280SensorValues*/
  free(bmp280SensorValues.celsius);
  free(bmp280SensorValues.fahrenheit);
  free(bmp280SensorValues.pascal);
  free(bmp280SensorValues.hectoPascal);
}

void setup() {
  Serial.begin(SERIAL_MONITOR_BOUD_RATE);
  matrixOutput.begin();

  Serial.println("setup: sensors initialization...");
  dht.begin();
  bmp.begin(0x76);
  defaultSampling(bmp);
  Serial.println("setup: sensors initialization done");

  Serial.println("setup: wifi initialization...");
  if(WiFi.status() == WL_NO_MODULE) {
    Serial.println("setup: communication with wifi module failed");
    isSetupDone = false;
    isWifiActive = false;
    matrixOutput.renderBitmap(MATRIX_OUTPUT_SETUP_FAILED, 8, 12);
  }
  else
  {
    Serial.println("setup: wifi firmware version check...");
    String installedFirmwareVersion = WiFi.firmwareVersion();
    if(installedFirmwareVersion < WIFI_FIRMWARE_LATEST_VERSION)
    {
      Serial.println("setup: installed firmware version " + installedFirmwareVersion);
      Serial.println("setup: latest firmware version " + String(WIFI_FIRMWARE_LATEST_VERSION));
      Serial.println("setup: please upgrade the firmware");
    }
    else
    {
      Serial.println("setup: installed firmware version " + String(WIFI_FIRMWARE_LATEST_VERSION));
    }
    Serial.println("setup: wifi firmware version check done");

    
    while( (status != WL_CONNECTED) && (wifiBeginCounter < 5) )
    {
      Serial.print("setup: attempting to connect to SSID "); Serial.println(ssid);
      status = WiFi.begin(ssid, pass);
      wifiBeginCounter++;
    }

    if(status == WL_CONNECTED)
    {
      isSetupDone = true;
      isWifiActive = true;

      /*set CA certificate*/
      client.setCACert(CA_CERT);

      matrixOutput.renderBitmap(MATRIX_OUTPUT_STATUS_OK, 8, 12);

      Serial.print("setup: wifi connected to SSID "); Serial.println(ssid);
    }
    else
    {
      isSetupDone = true;
      isWifiActive = false;

      matrixOutput.renderBitmap(MATRIX_OUTPUT_WIFI_CONNECTION_FAILED, 8, 12);

      Serial.print("setup: unable to connect to SSID "); Serial.println(ssid);
    }
  }
}

void loop() {
  if( (isSetupDone == true) && (isWifiActive == true) )
  {
    if(millis() - timeNowWifiTest > periodMillisWifiTest) {
      timeNowWifiTest = millis();
      Serial.println("loop: 15 seconds passed");
      Serial.println("loop: wifi connection test...");
      wifiConnectionTest();
      Serial.flush();
      Serial.println();
      Serial.println();
    }

    if(millis() - timeNowStatusSending > periodMillisStatusSending) {
      timeNowStatusSending = millis();
      if(WiFi.status() == WL_CONNECTED) {
        Serial.println("loop: 1 minute passed");
        Serial.println("loop: sending status to server...");
        sendStatusToServer();
        Serial.flush();
        Serial.println();
        Serial.println();
        matrixOutput.renderBitmap(MATRIX_OUTPUT_STATUS_OK, 8, 12);
      }
      else
      {
        matrixOutput.renderBitmap(MATRIX_OUTPUT_WIFI_CONNECTION_FAILED, 8, 12);
      }
    }

    if(millis() - timeNowDataSending > periodMillisDataSending){
      timeNowDataSending = millis();
      if(WiFi.status() == WL_CONNECTED) {
        Serial.println("loop: 3 minute passed");
        Serial.println("loop: sending data to server...");
        sendDataToServer();
        Serial.flush();
        Serial.println();
        Serial.println();
        matrixOutput.renderBitmap(MATRIX_OUTPUT_STATUS_OK, 8, 12);
      }
      else
      {
        matrixOutput.renderBitmap(MATRIX_OUTPUT_WIFI_CONNECTION_FAILED, 8, 12);
      }
    }

    if(millis() - timeNowOneHourRestart > periodMillisOneHourRestart) {
      timeNowOneHourRestart = millis();
      Serial.println("loop: 1 hour passed");
      Serial.println("loop: Arduino Restart...");
      delay(2000);
      NVIC_SystemReset();
    }
  }
  else
  {
    matrixOutput.renderBitmap(MATRIX_OUTPUT_SETUP_FAILED, 8, 12);
  }
}