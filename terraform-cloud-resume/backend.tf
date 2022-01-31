#Partially configured
#The remaining configuration is provided as part of terraform init
#with files in env/*.s3.tfbackend
#eg: terraform init -backend-config="env/prod.s3.tfbackend"
terraform {
  backend "s3" {}
}