# Generated by Django 3.2 on 2021-04-29 13:28

from django.db import migrations, models


class Migration(migrations.Migration):

    dependencies = [
        ('Emo', '0001_initial'),
    ]

    operations = [
        migrations.AddField(
            model_name='myemotion',
            name='Current_Time',
            field=models.TimeField(default=45),
            preserve_default=False,
        ),
    ]
