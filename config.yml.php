<?php

# config file
# 
# contains basic configuration settings and such
---

db:
	user: "root"
	password: ""
	host: "localhost"
	database: "small"

site:
  name: "Small Playground"
  url: "http://localhost/~don/small"

  # a random salting string. Eventually, when this config file gets 
  # generated in the install, this should be done by that, and not
  # by hand. For now, just pick some random string. It'll be fun, I
  # promise...
  salt: "smallSalt_$@?!=/>wordup"

#?>