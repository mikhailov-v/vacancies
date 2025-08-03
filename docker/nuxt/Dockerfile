FROM node:24

WORKDIR /app

COPY ./frontend/package*.json ./
RUN corepack enable && yarn install

EXPOSE 3000
CMD ["yarn", "dev"]
