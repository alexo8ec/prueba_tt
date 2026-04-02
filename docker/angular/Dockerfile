FROM node:20-alpine AS build
LABEL maintainer="Alex Mite <alexo8ec@hotmail.com>"
WORKDIR /app

COPY package*.json ./
RUN npm install

COPY . .
RUN npm run build

FROM nginx:alpine
RUN rm -rf /usr/share/nginx/html/*
COPY docker/default.conf /etc/nginx/conf.d/default.conf
COPY --from=build /app/dist/dashboard-app/browser/ /usr/share/nginx/html/
EXPOSE 80