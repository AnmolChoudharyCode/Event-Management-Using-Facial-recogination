from django.http import HttpResponse
from django.shortcuts import render
from django.views.decorators import gzip
from django.http import StreamingHttpResponse
import cv2
import threading
# emo
import matplotlib.pyplot as plt
import tensorflow as tf
from keras.models import load_model
# from time import sleep
from keras.preprocessing.image import img_to_array
# from keras.preprocessing import image
import cv2
import numpy as np
from Emo.models import Myemotion
import datetime


def index(request):
    return render(request, 'index.html')


class VideoCamera(object):
    def __init__(self):
        self.video = cv2.VideoCapture(0)
        (self.grabbed, self.frame) = self.video.read()
        threading.Thread(target=self.update, args=()).start()

    def __del__(self):
        self.video.release()

    def get_frame(self):
        image = self.frame
        _, jpeg = cv2.imencode('.jpg', image)
        return jpeg.tobytes()

    def update(self):
        # while True:
        #      (self.grabbed, self.frame) = self.video.read()

        # Initializing face classifier
        face_classifier = cv2.CascadeClassifier('D:\Temp mix\Myproject\emodet\haarcascade_frontalface_default.xml')
        # Initializing emotion detection file trained using keras
        classifier = load_model('D:\Temp mix\Myproject\emodet\Emotion_Detection.h5')

        class_labels = ['Angry', 'Happy', 'Neutral', 'Sad', 'Surprise']

        emotionlist = []
        Angry = 0
        Happy = 0
        Neutral = 0
        Sad = 0
        Surprise = 0
        c = 0
        # while True:
        while (c != 75):

            # ret, frame = self.video.read()
            # Grab a single frame of video
            (self.grabbed, self.frame) = self.video.read()
            labels = []
            # Convert it to gray scale
            gray = cv2.cvtColor(self.frame, cv2.COLOR_BGR2GRAY)
            # Pass it to the face classifier
            # detectMultiScale will detect the face in grey scale image and data will be stored in variable faces
            faces = face_classifier.detectMultiScale(gray, 1.3, 5)
            # x,y,w,h are the coordinates of faces
            for (x, y, w, h) in faces:
                cv2.rectangle(self.frame, (x, y), (x + w, y + h), (255, 0, 0), 2)
                roi_gray = gray[y:y + h, x:x + w]
                # Resize image in 48x48 because of the trained model give more accuracy on 48x48
                roi_gray = cv2.resize(roi_gray, (48, 48), interpolation=cv2.INTER_AREA)

                # Converting images in array
                if np.sum([roi_gray]) != 0:
                    roi = roi_gray.astype('float') / 255.0
                    roi = img_to_array(roi)
                    roi = np.expand_dims(roi, axis=0)

                    # make a prediction on the ROI, then lookup the class

                    preds = classifier.predict(roi)[0]
                    # preds give the probability
                    print("\nprediction = ", preds)
                    # select the maximum probability
                    # return the index of max probability
                    label = class_labels[preds.argmax()]
                    print("\nprediction max = ", preds.argmax())
                    print("\nlabel = ", label)
                    emotionlist.append(label)
                    label_position = (x, y)
                    cv2.putText(self.frame, label, label_position, cv2.FONT_HERSHEY_SIMPLEX, 2, (0, 255, 0), 3)
                else:
                    cv2.putText(self.frame, 'No Face Found', (20, 60), cv2.FONT_HERSHEY_SIMPLEX, 2, (0, 255, 0), 3)
                print("\n\n")
                c = c + 1
            # cv2.imshow('Emotion Detector', self.frame)
            # if cv2.waitKey(1) & 0xFF == ord('q'):
            #      break

        print(emotionlist)
        for m in emotionlist:
            if m == 'Angry':
                Angry = Angry + 1
            if m == 'Happy':
                Happy = Happy + 1
            if m == 'Neutral':
                Neutral = Neutral + 1
            if m == 'Sad':
                Sad = Sad + 1
            if m == 'Surprise':
                Surprise = Surprise + 1
        print(Angry, Happy, Neutral, Sad, Surprise)
        if((Angry+Sad)>=(Happy+Neutral+Surprise)):
            print("Event is unsuccessful")
            mytext = "Event is unsuccessful"
        if((Angry+Sad)<(Happy+Neutral+Surprise)):
            print("Event is successful")
            mytext = "Event is successful"
        ins = Myemotion(Angry_count = Angry, Happy_count = Happy, Neutral_count = Neutral, Sad_count = Sad, Surprise_count = Surprise, Final_text = mytext)
        ins.save()
        self.video.release()
        cv2.destroyAllWindows()
        t = gengraph(Angry, Happy, Neutral, Sad, Surprise)
    # return render(request,'index.html')

def gen(camera):
    while True:
        frame = camera.get_frame()
        yield (b'--frame\r\n'
               b'Content-Type: image/jpeg\r\n\r\n' + frame + b'\r\n\r\n')


@gzip.gzip_page
def livefe(request):
    try:
        cam = VideoCamera()
        return StreamingHttpResponse(gen(cam), content_type="multipart/x-mixed-replace;boundary=frame")
    except:  # This is bad! replace it with proper handling
        pass


from django.shortcuts import render

# Create your views here.
def gengraph(Angry_c,Happy_c,Neutral_c, Sad_c, Surprise_c):
    import matplotlib.pyplot as plt

    # defining labels
    activities = ['Angry', 'Happy', 'Neutral', 'Sad', 'Surprise']

    # portion covered by each label
    slices = [Angry_c, Happy_c, Neutral_c, Sad_c, Surprise_c]

    # color for each label
    colors = ['r', 'y', 'g', 'b', 'pink']

    # plotting the pie chart
    plt.pie(slices, labels=activities, colors=colors,
            startangle=200, shadow=True, explode=(0, 0, 0, 0, 0),
            radius=1.2, autopct='%1.1f%%')

    # plotting legend
    plt.legend()
    # showing the plot
    plt.show()