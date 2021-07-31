FROM ubuntu:latest

# Install cron
RUN apt-get update
RUN apt-get install cron

# Add crontab file in the cron directory
ADD crontab /etc/cron.d/simple-cron

# Give execution rights on the cron job
RUN chmod 0644 /etc/cron.d/simple-cron

# Run the command on container startup
CMD cron
