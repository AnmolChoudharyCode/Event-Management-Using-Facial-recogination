import cv2
import pickle
import numpy as np 
import MySQLdb.cursors
import face_recognition

from PIL import Image
from os import listdir
from os.path import isdir
from datetime import datetime
from flask_sqlalchemy import SQLAlchemy
from flask_mysqldb import MySQL
from flask import Flask, render_template, url_for, request, redirect,session




# Initializing Flask and Database
app = Flask(__name__)
app.secret_key = "Secret Key"
app.config['MYSQL_HOST'] = 'localhost'
app.config['MYSQL_USER'] = 'root'
app.config['MYSQL_PASSWORD'] = ''
app.config['MYSQL_DB'] = 'eventmanagement'
mysql = MySQL(app)


# Global Variables
path_to_dataset = "./dataset/user"
users_face_encoding_list = []
users_info_list = []
users_id_list = []

# Data Class
class User():
    def __init__(self, uid, name, mobile, email, eid,firstDetectedOn ):
        self.uid = uid
        self.name = name
        self.mobile = mobile
        self.email = email
        self.eid = eid
        self.firstDetectedOn = firstDetectedOn

# Loading Users Data from Database
def load_data_from_database():
    with app.app_context():
        cursor = mysql.connection.cursor(MySQLdb.cursors.DictCursor)
        cursor.execute('SELECT * FROM user')
        rows = cursor.fetchall()
        for each in rows:
            user = User(each['uid'],each['name'],each['mobile'],each['email'],each['eid'],each['first_detected_on'])
            users_info_list.append(user)

# Generate Face Encodings
def load_user_encodings():
    # Loading Users Photos from dataset
    for user in listdir(path_to_dataset):
        path = path_to_dataset + '/' + user 
        if isdir(path):
            image_path = path + '/' + listdir(path)[0]
            image = face_recognition.load_image_file(image_path)
            user_encoding = face_recognition.face_encodings(image)[0]
            users_face_encoding_list.append(user_encoding)
            users_id_list.append(user)

# Get User Data Object from ID
def findUserInfoById(uid):
    for user in users_info_list:
        if user.uid == int(uid):
            return user
    return None

# Update User First Detected Time
def updateFirstDetectedOn(user):
    today = datetime.today()
    now = datetime.now()
    formatted_date = now.strftime("%d/%m/%Y %H:%M:%S")
    user.firstDetectedOn = formatted_date
    
    #Updating User Info in current List
    for index, user_from_array in enumerate(users_info_list):
        if user_from_array.uid == user.uid:
            users_info_list[index] = user

    #Updating User Info in Database
    with app.app_context():
        cursor = mysql.connection.cursor(MySQLdb.cursors.DictCursor)
        cursor.execute('UPDATE user SET first_detected_on = %s WHERE uid = %s',(formatted_date,user.uid))
        mysql.connection.commit()



# Loading Data from dataset & Database both
load_data_from_database()
load_user_encodings()

# Starting Live Stream
video_capture = cv2.VideoCapture(0)
face_locations = []
face_encodings = []
face_names = []
process_this_frame = True

while True:
    #Grab a Single Frame of a video
    ret, frame = video_capture.read()
    #Resize frame of video to 1/4 size for faster face recognition processing
    small_frame = cv2.resize(frame,(0,0),fx=0.25,fy=0.25)
    #Convert the image from BGR  color (which OpenCV users) to RGB color (which face_recognition_users)
    rgb_small_frame = small_frame[:,:,::-1]
    #Only process every other frame of video to save time
    if process_this_frame:
        face_locations = face_recognition.face_locations(rgb_small_frame)
        face_encodings = face_recognition.face_encodings(rgb_small_frame,face_locations)
        face_names = []
        for face_encoding in users_face_encoding_list:
            #see if the face is a match for the known face(s)
            matches = face_recognition.compare_faces(users_face_encoding_list,face_encoding)
            found_user = None
            face_distances = face_recognition.face_distance(users_face_encoding_list,face_encoding)
            best_match_index = np.argmin(face_distances)
            if matches[best_match_index]:
              
                found_user = findUserInfoById(users_id_list[best_match_index])
                if found_user != None :
                    if found_user.firstDetectedOn == None:
                        updateFirstDetectedOn(found_user)
                    
            face_names.append(found_user)
    process_this_frame = not process_this_frame


    # Display the results
    for (top, right, bottom, left), user in zip(face_locations, face_names):

        # Scale back up face locations since the frame we detected in was scaled to 1/4 size
        top *= 4
        right *= 4
        bottom *= 4
        left *= 4
        font = cv2.FONT_HERSHEY_SIMPLEX
   
        if(user == None):
            cv2.rectangle(frame, (left, top), (right, bottom), (255, 0, 0), 2)
            cv2.putText(frame, "Unknown User", (left + 6, bottom - 6), font, 1.0, (255, 0, 0), 1,cv2.LINE_AA)
        else:
            
            eventString = "Event Id: "+str(user.eid)
            cv2.rectangle(frame, (left, top), (right, bottom), (0, 255, 0), 2)
            cv2.putText(frame, user.name, (left + 6, bottom - 6), font, 1.0, (0, 255, 0), 1,cv2.LINE_AA)
            cv2.putText(frame, user.mobile, (left + 6, bottom + 36), font, 1.0, (0, 255, 0), 1,cv2.LINE_AA)
            cv2.putText(frame, eventString, (left + 6, bottom + 66), font, 1.0, (0, 255, 0), 1,cv2.LINE_AA)
        

    # Display the resulting image
    cv2.imshow('Video', frame)

    # Hit 'q' on the keyboard to quit!
    if cv2.waitKey(1) & 0xFF == ord('q'):
        break

# Release handle to the webcam
video_capture.release()
cv2.destroyAllWindows()



            
    








        

    