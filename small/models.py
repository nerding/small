from sqlalchemy import Column, Integer, String, DateTime, Text, ForeignKey
from sqlalchemy.orm import relationship, backref
from sqlalchemy.ext.declarative import declarative_base

Base = declarative_base()

class User(Base):
  __tablename__ = 'users'

  id        = Column(Integer, primary_key=True)
  email     = Column(String)
  password  = Column(String)
  name      = Column(String)
  posts     = relationship('Post', backref='author')

  def __init__(self, email, password, name):
    self.email = email
    self.password = password
    self.name = name


  def __repr__(self):
    return "<User('%s', '%s')>" % (self.email, self.name)


class Post(Base):
  __tablename__ = 'posts'

  id          = Column(Integer, primary_key=True)
  title       = Column(String)
  content     = Column(Text)
  timestamp   = Column(DateTime)
  author_id   = Column(Integer, ForeignKey('users.id'))