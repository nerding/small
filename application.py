#!/usr/bin/env python2

from flask import Flask
from small.models import *

app = Flask(__name__)


# display the index page
@app.route('/')
def index():
  return "<h1>Small</h1><h2>Because sometimes Medium is too large.</h2>"



if __name__ == '__main__':
  app.run(debug=True)