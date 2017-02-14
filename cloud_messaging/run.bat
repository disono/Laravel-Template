@echo off

start mongod --dbpath ./storage/database
timeout /t 3
start node index.js