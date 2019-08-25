#!/usr/bin/python
# Copyright (c) 2019 Demix Enterprises
# Author: Ryan Saunders

import Adafruit_DHT

sensor = Adafruit_DHT.DHT11
pin = 4
degree_sign= u'\N{DEGREE SIGN}'
humidity, temperature = Adafruit_DHT.read_retry(sensor, pin)

if humidity is not None and temperature is not None:
    print('Temperature: ' + str(temperature) + ' ' + degree_sign + 'C' + ' Humidity: ' + str(humidity) + ' %')
else:
    print('Failed to get reading. Try again!')

