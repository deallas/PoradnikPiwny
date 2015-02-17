;<?php die("Hack attempt"); ?>
[hash]
type = "SHA256"
salt = ""

[blocker]
enabled = "1"
attempts.max = "5"
attempts.time = "3600"
time = "2700"

[recaptcha]
enabled = "1"
min_attempts = "2"
public = ""
private = ""
theme = "clean"
lang = "en"

