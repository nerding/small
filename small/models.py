from sqlalchemy import Column, Integer, String
from sqlalchemy.ext.declarative import declarative_base

Base = declarative_base()

class User(Base):
  __tablename__ = 'users'

  id        = Column(Integer, primary_key=True)
  email     = Column(String)
  password  = Column(String)
  name      = Column(String)

  def __init__(self, email, password, name):
    self.email = email
    self.password = password
    self.name = name


  def __repr__(self):
    return "<User('%s', '%s')>" % (self.email, self.name)