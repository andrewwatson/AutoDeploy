
# AutoDeploy Bot #

The idea behind this project is to build a simple PHP app that gets update hooks from
GitHub and then publishes files from that project to S3 using the AWS SDK

All you have to do to get started is:

1. Set your GitHub Service Hook to push to the URL of wherever you install index.php
2. Check the GitHub project you want to update to S3 out in this directory using the default name
3. Set up a cron to run relay.php on a regular basis to fetch updates out of SQS and push the code to S3

