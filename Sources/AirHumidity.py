#!/usr/bin/env python
# -*- coding: utf-8 -*-

import grovepi
import time

counter = 0
while counter <=2:
    try:
        fichier = open("temperature.txt", "w")
	[temp,humidity] = grovepi.dht(4,1)
     	print("temperature =", temp,"degrey")
	time.sleep(2)
	fichier.write(str(temp))
	counter+=1
    except (IOError,TypeError) as e:
   	fichier.close()
	print("Error")
	exit()
