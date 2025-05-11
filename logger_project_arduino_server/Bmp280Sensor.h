#ifndef BMP_280_SENSOR
#define BMP_280_SENSOR

#include <Adafruit_BMP280.h>
#include "Types.h"


/*constants*/





/*defines*/





/*types*/




/*functions*/
void defaultSampling(Adafruit_BMP280 &bmp280Sensor);
Bmp280SensorValues updateBmp280Values(Bmp280SensorValues values, Adafruit_BMP280 &bmp280Sensor);
char * celsius(Adafruit_BMP280 &bmp280Sensor);
char * fahrenheit(Adafruit_BMP280 &bmp280Sensor);
char * pascal(Adafruit_BMP280 &bmp280Sensor);
char * hectoPascal(Adafruit_BMP280 &bmp280Sensor);

void defaultSampling(Adafruit_BMP280 &bmp280Sensor) {
  bmp280Sensor.setSampling(Adafruit_BMP280::MODE_NORMAL,     /* Operating Mode. */
                  Adafruit_BMP280::SAMPLING_X2,     /* Temp. oversampling */
                  Adafruit_BMP280::SAMPLING_X16,    /* Pressure oversampling */
                  Adafruit_BMP280::FILTER_X16,      /* Filtering. */
                  Adafruit_BMP280::STANDBY_MS_500); /* Standby time. */
}

Bmp280SensorValues updateBmp280Values(Bmp280SensorValues values, Adafruit_BMP280 &bmp280Sensor) {
  values.celsius = celsius(bmp280Sensor);
  values.fahrenheit = fahrenheit(bmp280Sensor);
  values.pascal = pascal(bmp280Sensor);
  values.hectoPascal = hectoPascal(bmp280Sensor);
  return values;
}

char * celsius(Adafruit_BMP280 &bmp280Sensor) {
  float celsiusFloat = bmp280Sensor.readTemperature();
  char * bmp280TempC = FloatToArrayChar(celsiusFloat);
  return bmp280TempC;
}

char * fahrenheit(Adafruit_BMP280 &bmp280Sensor) {
  float celsiusFloat = bmp280Sensor.readTemperature();
  float fahrenheitFloat = (celsiusFloat * 1.8) + 32;
  char * bmp280TempF = FloatToArrayChar(fahrenheitFloat);
  return bmp280TempF;
}

char * pascal(Adafruit_BMP280 &bmp280Sensor) {
  float atmoPressurePascal = bmp280Sensor.readPressure();
  char * bmp280AtmoPresPascal = FloatToArrayChar(atmoPressurePascal);
  return bmp280AtmoPresPascal;
}

char * hectoPascal(Adafruit_BMP280 &bmp280Sensor) {
  float atmoPressurePascal = bmp280Sensor.readPressure();
  char * bmp280AtmoPresHectoPascal = FloatToArrayChar(atmoPressurePascal / 100);
  return bmp280AtmoPresHectoPascal;
}

#endif /*include protection*/