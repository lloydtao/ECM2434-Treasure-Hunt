![](https://lh4.googleusercontent.com/pciFHGKpmbAOeM6wPxBB1WspAci6rbkkMh0Na4f_XV2UuQvmShofkQNWJgDC_A8YI6EX6hjvEWj6cnzaEPlQJOxiP2fYN5CsyW7ZyKi7t0Xrbrk_0XGbHKXpqdC8tpQ9tTpkFGRA)

## Continuous Deployment to Kubernetes

### Introduction
Campus Treks utilises a continuous integration and delivery pipeline, which is achieved through containerising the application and deploying to the IBM Cloud™ Kubernetes Service.

This document covers how to set up deployment, then develop, build, test and deploy the code.

### What does this mean?

Continuous integration allows us to actively and automatically develop the application throughout releases with extremely limited downtime.

Our source code is held within a Git repository. Our developer team builds updates to the application on the development branch.

1. The IBM Cloud™ delivery pipeline detects changes to the production branch,
    
2. The pipeline then builds a container image and uploads this image to the registry.
    
3. The app is deployed to the development environment.
    
4. Our developer team validates the changes to the development environment.
    
5. Once validated, the app is deployed to the production environment.

Additionally, we can automatically send notifications via Slack to track the deployment status.

### What does this achieve?

- Automatic deployment eliminates administrative overhead from your team.
    
- A new developer only has to worry about the source code, and not the deployment.
    
- No technical knowledge is necessary for deployment and normal maintenance.
    
- Security is improved as system resources are isolated with their own security policies.