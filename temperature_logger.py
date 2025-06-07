#!/usr/bin/env python

# Get the temperature and insert it into the SQL, for reading later by index.php.

# This code is executed every periodically by cron job:
# * * * * * python /home/pi/Raspberry/Temperature/temperature_logger.py 28-041780f40cff
"""Raspberry Pi Temperature Database Logger"""

import os
import MySQLdb
#import mariadb
import sys
import datetime
import glob
import time
#import cgitb cgitb.enable()

os.system('sudo modprobe w1-gpio')
os.system('sudo modprobe w1-therm')

Temp2AbvTable = {
    100.00: 0.00,
    99.65: 6.64,
    98.95: 12.81,
    98.55: 17.80,
    98.05: 23.59,
    97.50: 27.99,
    97.11: 31.98,
    96.56: 36.14,
    96.00: 39.09,
    95.63: 42.35,
    95.22: 44.67,
    94.84: 47.39,
    94.56: 49.42,
    94.10: 51.35,
    93.73: 53.04,
    93.40: 54.49,
    93.10: 55.94,
    92.78: 57.17,
    92.42: 58.39,
    92.10: 59.50,
    91.80: 60.61,
    91.46: 61.50,
    91.12: 62.50,
    90.84: 63.48,
    90.54: 64.36,
    90.30: 65.14,
    90.02: 65.91,
    89.79: 66.59,
    89.56: 67.26,
    89.36: 67.83,
    89.16: 68.40,
    88.94: 68.97,
    88.72: 69.53,
    88.52: 70.01,
    88.32: 70.48,
    88.11: 70.94,
    87.92: 71.41,
    87.77: 71.78,
    87.62: 72.23,
    87.47: 72.61,
    87.32: 72.97,
    87.02: 73.34,
    86.71: 73.71,
    86.41: 74.07,
    86.11: 74.44,
    86.02: 74.71,
    85.94: 74.97,
    85.85: 75.24,
    85.76: 75.52,
    85.67: 75.78,
    85.58: 75.96,
    85.50: 76.23,
    85.41: 76.41,
    85.32: 76.68,
    85.22: 76.94,
    85.13: 77.12,
    85.04: 77.30,
    84.95: 77.56,
    84.86: 77.74,
    84.79: 77.92,
    84.70: 78.09,
    84.62: 78.26,
    84.54: 78.44,
    84.46: 78.62,
    84.37: 78.71,
    84.28: 78.88,
    84.20: 79.05,
    84.12: 79.28,
    84.04: 79.40,
    83.96: 79.49,
    83.87: 79.66,
    83.79: 79.75,
    83.72: 79.92,
    83.65: 80.10,
    83.58: 80.18,
    83.51: 80.27,
    83.44: 80.44,
    83.37: 80.62,
    83.30: 80.70,
    83.24: 80.87,
    83.18: 80.96,
    83.13: 81.04,
    83.07: 81.12,
    83.01: 81.30,
    82.95: 81.38,
    82.89: 81.47,
    82.83: 81.64,
    82.78: 81.78,
    82.72: 81.81,
    82.66: 81.89,
    82.60: 82.07,
    82.54: 82.15,
    82.48: 82.23,
    82.42: 82.32,
    82.37: 82.49,
    82.30: 82.58,
    82.25: 82.66,
    82.19: 82.82,
    82.13: 82.91,
    82.07: 82.99,
    82.02: 83.08,
    81.96: 83.14,
    81.91: 83.25,
    81.86: 83.33,
    81.81: 83.42,
    81.76: 83.58,
    81.70: 83.67,
    81.65: 83.75,
    81.60: 83.83,
    81.55: 83.92,
    81.49: 84.00,
    81.44: 84.08,
    81.38: 84.17,
    81.33: 84.33,
    81.28: 84.42,
    81.23: 84.50,
    81.18: 84.58,
    81.13: 84.74,
    81.08: 84.82,
    81.04: 84.91,
    81.00: 84.99,
    80.96: 85.16,
    80.91: 85.24,
    80.86: 85.32,
    80.80: 85.40,
    80.74: 85.57,
    80.64: 85.72,
    80.59: 85.81,
    80.54: 85.89,
    80.49: 85.98,
    80.44: 86.13,
    80.39: 86.22,
    80.34: 86.38,
    80.29: 86.46,
    80.23: 86.62,
    80.19: 86.70,
    80.14: 86.87,
    80.09: 86.94,
    80.04: 87.02,
    80.00: 87.18,
    79.95: 87.27,
    79.91: 81.42,
    79.86: 81.58,
    79.81: 87.67,
    79.77: 87.82,
    79.72: 87.98,
    79.68: 88.14,
    79.63: 88.22,
    79.59: 88.38,
    79.54: 88.54,
    79.51: 88.70,
    79.45: 88.78,
    79.40: 88.93,
    79.36: 89.09,
    79.32: 89.25,
    79.27: 89.40,
    79.23: 89.56,
    79.18: 89.64,
    79.13: 89.79,
    79.09: 89.95,
    79.05: 90.10,
    79.01: 90.33,
    78.97: 90.48,
    78.93: 90.64,
    78.88: 90.87,
    78.84: 91.02,
    78.81: 91.17,
    78.77: 91.40,
    78.72: 91.56,
    78.69: 91.78,
    78.65: 91.93,
    78.61: 92.16,
    78.58: 92.38,
    78.54: 92.60,
    78.53: 92.82,
    78.48: 93.04,
    78.45: 93.27,
    78.41: 93.48,
    78.39: 93.70,
    78.35: 93.92,
    78.32: 94.13,
    78.30: 94.42,
    78.27: 94.63,
    78.26: 95.13,
    78.24: 95.42,
    78.23: 95.69,
    78.21: 95.97,
    78.20: 96.24,
    78.19: 96.58,
    78.18: 96.86,
    78.17: 97.18
}

# Keep this script running forever.
while True:
	
	# Connect to the SQL DB.
	db2 = MySQLdb.connect(user="pi", passwd="1q2w3e4r5t", db="web")
	cur2 = db2.cursor()

	# Find the temp sensor, based on the argument(s) given when calling this script.
	if len(sys.argv) > 1:
		sensor_path = "/sys/bus/w1/devices/"
		sensor_id_list = sys.argv[1:]
	else:
		sensor_path = "/home/pi/Raspberry/Temperature/test/"
		sensor_id_list = ["99-999999999999"]
    

	for sensor_id in sensor_id_list:
		# Read the temperature from the sensor's file created by Linux
		sensor_file = open(sensor_path + sensor_id + "/w1_slave", "r")
		sensor_lines = sensor_file.readlines()
		sensor_file.close()
		sensor_output = sensor_lines[1].find("t=")

		# Convert the temperature value in the file into degrees C.
		if sensor_output != -1:
			sensor_val = float(sensor_lines[1].strip()[sensor_output+2:])/1000.0
        
		else:
			sensor_val = -273.16

		# Round the degrees C to 2 values.
		sensor_val = round(sensor_val, 2)

		# Find the closest temperature value match in the Temp2ABVTable table.
		sensor_val_closest = min(Temp2AbvTable.keys(), key=lambda key: abs(key-sensor_val))

		# Find the temperature in the Temp2ABVTable table and output the associated ABV.
		if 78 < sensor_val <= 100:
			abv_val = Temp2AbvTable[sensor_val_closest]		
		else:
			abv_val = 0.0
		

		#Add the sensor ID, temperature and ABV values to the SQL DB
		statement2 = "INSERT INTO temperature (sensor_id,sensor_val,abv_val) VALUES (%s, %s, %s)"
		data2 = (sensor_id, sensor_val, abv_val)
		try:
			cur2.execute(statement2, data2)
			db2.commit()        
		except:
			db2.rollback()
        
		# Add values to a TXT file.
		# Open file
		DistillationData_path = "/var/www/html/data/"
		DistillationData_file_name = "DistData" + datetime.datetime.now().strftime("%Y%m%d") + ".txt" 
		os.umask(0)
		DistillationData_file = open(os.open(DistillationData_path + DistillationData_file_name, os.O_CREAT | os.O_WRONLY, 0o777), 'a')   
		# Write the date, time, Temp and ABV values into the file.
		DistillationData_file.write(datetime.datetime.now().strftime("%H:%M:%S") + "  ")   
		DistillationData_file.write("Actual Temp is " + str(sensor_val) + "  ")
		DistillationData_file.write("Closest Temp is " + str(sensor_val_closest) + "  ")
		DistillationData_file.write("ABV is " + str(abv_val) + "%  \n")

		DistillationData_file.close
		
		# Wait a few seconds before taking another temperature reading.	
		time.sleep(10)

	# Close the SQL DB connection before exiting.
	cur2.close()
	db2.close()
