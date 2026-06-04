pipeline {
    agent any

    environment {
        APP_NAME = 'php-demoapp'
        IMAGE_NAME = 'php-demoapp:latest'
        MYSQL_IMAGE = 'php-demoapp-mysql:latest'
        KIND_CLUSTER = 'php-cluster'
        K8S_DIR = 'k8s'
        KUBECONFIG = '/var/jenkins_home/.kube/config'
    }

    stages {
        stage('Check Tools') {
            steps {
                sh 'docker --version'
                sh 'docker ps'
                sh 'kubectl --kubeconfig=$KUBECONFIG get nodes'
                sh 'kind version'
            }
        }

        stage('Build Docker Images') {
            steps {
                sh 'docker build -t $IMAGE_NAME .'
                sh 'docker build -t $MYSQL_IMAGE mysql/'
            }
        }

        stage('Load Images Into Kind') {
            steps {
                sh 'kind load docker-image $IMAGE_NAME --name $KIND_CLUSTER'
                sh 'kind load docker-image $MYSQL_IMAGE --name $KIND_CLUSTER'
            }
        }

        stage('Deploy MySQL') {
            steps {
                sh 'kubectl --kubeconfig=$KUBECONFIG apply -f $K8S_DIR/mysql.yaml'
                sh 'kubectl --kubeconfig=$KUBECONFIG rollout status deployment/mysql --timeout=120s'
            }
        }

        stage('Deploy PHP App') {
            steps {
                sh 'kubectl --kubeconfig=$KUBECONFIG apply -f $K8S_DIR/deployment.yaml'
                sh 'kubectl --kubeconfig=$KUBECONFIG apply -f $K8S_DIR/service.yaml'
                sh 'kubectl --kubeconfig=$KUBECONFIG rollout restart deployment/$APP_NAME'
                sh 'kubectl --kubeconfig=$KUBECONFIG rollout status deployment/$APP_NAME --timeout=120s'
            }
        }

        stage('Show Kubernetes Status') {
            steps {
                sh 'kubectl --kubeconfig=$KUBECONFIG get pods -o wide'
                sh 'kubectl --kubeconfig=$KUBECONFIG get svc'
                sh 'kubectl --kubeconfig=$KUBECONFIG get pvc'
            }
        }
    }
}