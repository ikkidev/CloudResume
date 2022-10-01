##Prerequisite:
1. AWS account to provision resources
   ``` 
    I use awsume to manage my aws profile configurations
    To assume a profile simply do
        awsume dev
        awsume prod

    To configure an aws profile
        aws configure --profile dev

    To list all available profiles
        awsume --list-profiles
    ```
2. S3 bucket to store terraform remote state must have been created beforehand
following the name in the [tfbackend file](env/dev.s3.tfbackend)

##Provisioning:  
Environment files are defined in the [env directory](env)

To provision a module simply run from the module directory:  
- cd terraform-cloud-resume\modules\dynamodb
- terraform apply -var-file="..\..\env\dev.tfvars"

To provision the entire AWS resources for the cloud resume website run:  
- cd terraform-cloud-resume
- run terraform init -reconfigure -backend-config="env\dev.s3.tfbackend"
- terraform apply -var-file="env\dev.tfvars"