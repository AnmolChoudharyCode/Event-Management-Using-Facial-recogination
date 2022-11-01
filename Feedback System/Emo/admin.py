from django.contrib import admin
from Emo.models import Myemotion
# Register your models here.

class MyemotionAdmin(admin.ModelAdmin):
    list_display = ('Angry_count', 'Happy_count', 'Neutral_count', 'Sad_count', 'Surprise_count', 'Final_text')

admin.site.register(Myemotion, MyemotionAdmin)