terraform {
  backend "s3" {
    skip_requesting_account_id  = true
    skip_credentials_validation = true
    skip_get_ec2_platforms      = true
    skip_metadata_api_check     = true
    endpoint                    = "https://sfo2.digitaloceanspaces.com"
    region                      = "us-east-1"
    bucket                      = "cs-infra-assets"
    key                         = "board-terraform.tfstate"
  }
}
