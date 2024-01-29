FROM node:20.11.0
WORKDIR /home/node/app
COPY ./front .
RUN npm run build
CMD ["npm", "run", "dev"]
