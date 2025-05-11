#ifndef DHT_11_SENSOR
#define DHT_11_SENSOR

#include "DHT.h"
#include "Types.h"


/*constants*/




/*defines*/




/*types*/




/*functions*/
Dht11SensorValues updateDht11Values(Dht11SensorValues values, DHT &dht11Sensor);
char * celsius(DHT &dht11Sensor);
char * fahrenheit(DHT &dht11Sensor);
char * humidity(DHT &dht11Sensor);

Dht11SensorValues updateDht11Values(Dht11SensorValues values, DHT &dht11Sensor) {
  values.celsius = celsius(dht11Sensor);
  values.fahrenheit = fahrenheit(dht11Sensor);
  values.humidity = humidity(dht11Sensor);
  return values;
}

char * celsius(DHT &dht11Sensor) {
  float tempCFloat = dht11Sensor.readTemperature();
  char * dht11TempC = FloatToArrayChar(tempCFloat);
  return dht11TempC;
}

char * fahrenheit(DHT &dht11Sensor) {
  float TempFFloat = dht11Sensor.readTemperature(true);
  char * dht11TempF = FloatToArrayChar(TempFFloat);
  return dht11TempF;
}

char * humidity(DHT &dht11Sensor) {
  float humidityFloat = dht11Sensor.readHumidity();
  char * dht11Hum = FloatToArrayChar(humidityFloat);
  return dht11Hum;
}


#endif /*include protection*/