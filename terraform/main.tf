provider "aws" {
    region     = "ap-northeast-2"
}

resource "aws_security_group" "rds_seoul" {
  name = "rds_seoul"
  ingress {
    from_port       = 3306
    to_port         = 3306
    protocol        = "tcp"
    cidr_blocks = ["0.0.0.0/0"]
  }
  egress {
    from_port   = 0
    to_port     = 0
    protocol    = "-1"
    cidr_blocks = ["0.0.0.0/0"]
  }
}

resource "aws_db_instance" "myinstance" {
  engine               = "mysql"
  identifier           = "myrdsinstance"
  allocated_storage    =  20
  engine_version       = "5.7"
  instance_class       = "db.t3.micro"
  username             = "root"
  password             = "rootadmin007"
  parameter_group_name = "default.mysql5.7"
  vpc_security_group_ids = ["${aws_security_group.rds_seoul.id}"]
  skip_final_snapshot  = true
  publicly_accessible =  true
  db_name              = "demobase"
}

module "ecrRepo" {
  source        = "./modules/ecr"
  ecr_repo_name = local.ecr_repo_name
}

module "ecsCluster" {
  source = "./modules/ecs"

  demo_app_cluster_name = local.demo_app_cluster_name
  availability_zones    = local.availability_zones

  demo_app_task_famliy         = local.demo_app_task_famliy
  ecr_repo_url                 = module.ecrRepo.repository_url
  container_port               = local.container_port
  demo_app_task_name           = local.demo_app_task_name
  ecs_task_execution_role_name = local.ecs_task_execution_role_name

  application_load_balancer_name = local.application_load_balancer_name
  target_group_name              = local.target_group_name
  demo_app_service_name          = local.demo_app_service_name
}