#!/usr/bin/env python2

from flask import Flask

app = Flask(__name__)


# display the index page
@app.route('/')
def index():
  return "Hello World!"


# display an individual post
@app.route('/posts/<int:year>/<int:month>/<int:day>/')
def show_post(year, month, day):
  return str(day) + " " + str(month) + " " + str(year)



if __name__ == '__main__':
  app.run(debug=True)