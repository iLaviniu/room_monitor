#ifndef CONSTANTS_DEFINES_TYPES
#define CONSTANTS_DEFINES_TYPES

#include "WiFiS3.h"
#include "Secrets.h"

/*variables*/
uint8_t                 ETHERNET_MAC[]     = { 0xDE, 0x77, 0x42, 0x33, 0x07, 0xED };

/*constants*/
const int                     HTTP_PORT          =  443;
const char                    HTTP_METHOD[]      = "POST";
const char                    HOST_NAME[]        = "www.nestinbase.com";
const char                    DATA_PATH_NAME[]   = "/monitor/app/incoming/redirection_data.php";
const char                    STATUS_PATH_NAME[] = "/monitor/app/incoming/redirection_status.php";
const char                    JSON_LINE_1[]      = "{\"DATA_STATUS\": \"NEW_DATA\",\"BMP280\": {\"temperature\": {\"celsius\": \"";
const unsigned int            JSON_LINE_1_SIZE   = strlen(JSON_LINE_1);
const char                    JSON_LINE_2[]      = "\",\"fahrenheit\": \"";
const unsigned int            JSON_LINE_2_SIZE   = strlen(JSON_LINE_2);
const char                    JSON_LINE_3[]      = "\"},\"atmospheric_pressure\": {\"Pa\": \"";
const unsigned int            JSON_LINE_3_SIZE   = strlen(JSON_LINE_3);
const char                    JSON_LINE_4[]      = "\",\"hPa\": \"";
const unsigned int            JSON_LINE_4_SIZE   = strlen(JSON_LINE_4);
const char                    JSON_LINE_5[]      = "\"}},\"DHT11\": {\"temperature\": {\"celsius\": \"";
const unsigned int            JSON_LINE_5_SIZE   = strlen(JSON_LINE_5);
const char                    JSON_LINE_6[]      = "\",\"fahrenheit\": \"";
const unsigned int            JSON_LINE_6_SIZE   = strlen(JSON_LINE_6);
const char                    JSON_LINE_7[]      = "\"},\"humidity\": {\"%\": \"";
const unsigned int            JSON_LINE_7_SIZE   = strlen(JSON_LINE_7);
const char                    JSON_LINE_8[]      = "\"}},\"PERIODICITY_MINUTES\": \"";
const unsigned int            JSON_LINE_8_SIZE   = strlen(JSON_LINE_8);
const char                    JSON_LINE_9[]      = "\"}";
const unsigned int            JSON_LINE_9_SIZE   = strlen(JSON_LINE_9);


byte MATRIX_OUTPUT_STATUS_OK[8][12] = {
  { 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 },
  { 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 },
  { 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 },
  { 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 },
  { 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 },
  { 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 },
  { 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 },
  { 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 }
};

byte MATRIX_OUTPUT_WIFI_CONNECTION_FAILED[8][12] = {
  { 1, 1, 1, 1, 1, 0, 0, 1, 1, 1, 1, 1 },
  { 1, 0, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0 },
  { 1, 0, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0 },
  { 1, 0, 0, 0, 0, 0, 0, 1, 1, 1, 0, 0 },
  { 1, 0, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0 },
  { 1, 0, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0 },
  { 1, 0, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0 },
  { 1, 1, 1, 1, 1, 0, 0, 1, 0, 0, 0, 0 }
};

byte MATRIX_OUTPUT_SETUP_FAILED[8][12] = {
  { 1, 1, 1, 1, 1, 0, 0, 1, 1, 1, 1, 1 },
  { 1, 0, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0 },
  { 1, 0, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0 },
  { 1, 0, 0, 0, 0, 0, 0, 1, 1, 1, 0, 0 },
  { 1, 1, 1, 1, 1, 0, 0, 1, 0, 0, 0, 0 },
  { 0, 0, 0, 0, 1, 0, 0, 1, 0, 0, 0, 0 },
  { 0, 0, 0, 0, 1, 0, 0, 1, 0, 0, 0, 0 },
  { 1, 1, 1, 1, 1, 0, 0, 1, 0, 0, 0, 0 }
};

/*defines*/
#define SERIAL_MONITOR_BOUD_RATE       (unsigned long)9600
#define DHTPIN                         (unsigned int)2
#define DHTTYPE                        (uint8_t)DHT11             


/*types*/
struct Dht11SensorValues {
  char * celsius;
  char * fahrenheit;
  char * humidity;
};

struct Bmp280SensorValues {
  char * celsius;
  char * fahrenheit;
  char * pascal;
  char * hectoPascal;
};


/*functions*/
unsigned int calculateHttpContentLength(Dht11SensorValues dht11SensorValues, Bmp280SensorValues bmp280SensorValues, char * periodicityMinutesChar);
char * FloatToArrayChar(float valueAsFloat);
byte CharArraySize(char * arrayChar);
char * UIntToArrayChar(unsigned int number);
char * ULongToArrayChar(unsigned long number);
void copyMemmoryToBuffer(char * httpResponseStart, char * buffer);

unsigned int calculateHttpContentLength(Dht11SensorValues dht11SensorValues, Bmp280SensorValues bmp280SensorValues, char * periodicityMinutesChar) {
  int length = 0;
  
  length += JSON_LINE_1_SIZE;
  length += JSON_LINE_2_SIZE;
  length += JSON_LINE_3_SIZE;
  length += JSON_LINE_4_SIZE;
  length += JSON_LINE_5_SIZE;
  length += JSON_LINE_6_SIZE;
  length += JSON_LINE_7_SIZE;
  length += JSON_LINE_8_SIZE;
  length += JSON_LINE_9_SIZE;

  length += (int)CharArraySize(dht11SensorValues.celsius);
  length += (int)CharArraySize(dht11SensorValues.fahrenheit);
  length += (int)CharArraySize(dht11SensorValues.humidity);
  length += (int)CharArraySize(bmp280SensorValues.celsius);
  length += (int)CharArraySize(bmp280SensorValues.fahrenheit);
  length += (int)CharArraySize(bmp280SensorValues.pascal);
  length += (int)CharArraySize(bmp280SensorValues.hectoPascal);
  length += (int)CharArraySize(periodicityMinutesChar);

  return length;
}

char * FloatToArrayChar(float valueAsFloat) {
  char * result = (char*)malloc(16 * sizeof(char));
  double valDouble = (double)(valueAsFloat);
  double fractPart;
  double intPart;
  fractPart = modf(valDouble, &intPart);
  double twoDecimals = abs(fractPart * 100); /*100 because two decimals needed*/
  sprintf(result, "%ld.%ld", (long)intPart, (long)twoDecimals);
  return result;
}

byte CharArraySize(char * arrayChar) {
  byte index = 0;
  while(arrayChar[index] != '\0') {
    index++;
  }
  return index;
}

char * UIntToArrayChar(unsigned int number) {
  char * result = (char*)malloc(10 * sizeof(char));
  sprintf(result, "%d", number);
  return result;
}

char * ULongToArrayChar(unsigned long number) {
  char * result = (char*)malloc(10 * sizeof(char));
  sprintf(result, "%lu", number);
  return result;
}

void copyMemmoryToBuffer(char * httpResponseStart, char * buffer) {

  while((*httpResponseStart) != '\0') {
    *buffer = *httpResponseStart;
    httpResponseStart++;
    buffer++;
    *buffer = '\0';
  }

}

#endif /*include protection*/