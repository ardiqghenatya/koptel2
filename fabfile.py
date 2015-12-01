# WIW deployment with fabric

from fabric.api import settings, abort, run, cd, local, lcd, env
from fabric.contrib.console import confirm

# COMMAND : fab -R prod prod
env.roledefs = {
    'test': ['oktagon@oktagon.stagingapps.net'],
    'prod': ['oktagon@oktagon.stagingapps.net']
} 

def test():
  code_dir = 'app/api/core'
  local('git push origin master')
  with cd(code_dir):
    run("git pull origin master")
    run("./artisan migrate")
    run("./artisan db:seed")

def prod():
  code_dir = 'view'
  local('git push origin master')
  with cd(code_dir):
    run("git pull origin master")


def dump_api():
  #gPu9gW5kgTGD4z48
  code_dir = 'www'
  with cd(code_dir):
      run('mysqldump oktagon_db -u oktagon -pgPu9gW5kgTGD4z48 | gzip > db-oktagon-dump.gz')
  # Source Copy / download file dari server    
  local('scp oktagon@oktagon.stagingapps.net:www/db-oktagon-dump.gz ../')
  
def load_dump():
  dump_file = '../db-oktagon-dump.gz'
  if local("test -e %s" % dump_file).succeeded:
      local("gunzip %s && mysql -u root %s < ../db-oktagon-dump" % (dump_file, 'oktagon')) #cat filename.gz | gunzip | psql dbname
