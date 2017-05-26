#!/usr/bin/env python

import RPi.GPIO as GPIO
import time, sys

FLOW_SENSOR = 24
GPIO.setwarnings(False)
GPIO.setmode(GPIO.BCM)
GPIO.setup(FLOW_SENSOR, GPIO.OUT, initial=GPIO.LOW)

GPIO.output(FLOW_SENSOR,GPIO.LOW)
#GPIO.cleanup()
#while True:
   # try:
	#print('changement state')
        #time.sleep(1)
    #except KeyboardInterrupt:
       # print '\ncaught keyboard interrupt!, bye'
        #GPIO.cleanup()
        #sys.exit()
    #except:
	#print'qsdjikosquhijsbn'
