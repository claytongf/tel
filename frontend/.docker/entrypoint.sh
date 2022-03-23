#!/bin/bash
chown -R node:node .
npm install
npm run build
npm run start
