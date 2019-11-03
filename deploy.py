from paramiko import *
import json
import os

CRED = json.load(open("credential.json"))
ROUTE = "public_html/wordpress/wp-content/plugins/"
BASE = "cd %s;" % ROUTE

client = SSHClient()
client.set_missing_host_key_policy(AutoAddPolicy())
client.connect(CRED["SSH_HOST"], username=CRED["USERNAME"], password=CRED["PASSWORD"])
sftp = client.open_sftp()

os.system("zip -r sexpert.zip ../sexpert")

sftp.put("sexpert.zip", ROUTE + "sexpert.zip")
_, stdout, _ = client.exec_command(BASE + 'rm -rf sexpert')
_, stdout, _ = client.exec_command(BASE + 'unzip sexpert.zip')
_, stdout, _ = client.exec_command(BASE + 'rm -rf sexpert/.idea')
_, stdout, _ = client.exec_command(BASE + 'rm -rf sexpert/.git')
_, stdout, _ = client.exec_command(BASE + 'rm -f sexpert/credential.json')

print([x for x in stdout])
client.close()