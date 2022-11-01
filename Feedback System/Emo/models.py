from django.db import models

# Create your models here.
class Myemotion(models.Model):
    Angry_count = models.IntegerField()
    Happy_count = models.IntegerField()
    Neutral_count = models.IntegerField()
    Sad_count = models.IntegerField()
    Surprise_count = models.IntegerField()
    Final_text = models.CharField(max_length=100)