pipeline {
    agent any

    environment {
        APP_NAME = 'php-demoapp'
        IMAGE_NAME = 'php-demoapp:latest'
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

        stage('Build Docker Image') {
            steps {
                sh 'docker build -t $IMAGE_NAME .'
            }
        }

        stage('Load Image Into Kind') {
            steps {
                sh 'kind load docker-image $IMAGE_NAME --name $KIND_CLUSTER'
            }
        }

        stage('Deploy To Kubernetes') {
            steps {
                sh 'kubectl --kubeconfig=$KUBECONFIG apply -f $K8S_DIR/'
                sh 'kubectl --kubeconfig=$KUBECONFIG rollout restart deployment/$APP_NAME'
                sh 'kubectl --kubeconfig=$KUBECONFIG rollout status deployment/$APP_NAME --timeout=120s'
            }
        }

        stage('Show Kubernetes Status') {
            steps {
                sh 'kubectl --kubeconfig=$KUBECONFIG get pods'
                sh 'kubectl --kubeconfig=$KUBECONFIG get svc'
            }
        }
    }
}