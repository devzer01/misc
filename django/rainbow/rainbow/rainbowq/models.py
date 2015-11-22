from django.db import models

# Create your models here.
class Query(models.Model):
    query = models.CharField(max_length=200)
    ipaddr = models.CharField(max_length=32)
    
    def __unicode__(self):  
        return self.query
