FROM node:20-alpine

# Set working directory
WORKDIR /var/www/web

# Install dependencies (cached layer)
COPY web/package*.json ./
RUN npm install

# Expose Nuxt dev port
EXPOSE 3000

# Start Nuxt dev server
CMD ["npm", "run", "dev", "--", "--host", "0.0.0.0"]
