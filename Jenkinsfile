pipeline {
    agent any

    environment {
        APP_NAME = 'php-demoapp'
        VERSION = "${BUILD_NUMBER}"
        NEXUS_URL = 'http://nexus:8081'
        NEXUS_REPO = 'php-artifacts'
    }

    stages {
        stage('PHP Syntax Check') {
            steps {
                sh '''
                find . -name "*.php" -print0 | xargs -0 -n1 php -l
                '''
            }
        }

        stage('Package App') {
            steps {
                sh '''
                rm -rf build
                mkdir -p build
                zip -r build/${APP_NAME}-${VERSION}.zip . \
                  -x "*.git*" "build/*" ".env"
                '''
            }
        }

        stage('Upload to Nexus') {
            steps {
                withCredentials([usernamePassword(
                    credentialsId: 'nexus-creds',
                    usernameVariable: 'NEXUS_USER',
                    passwordVariable: 'NEXUS_PASS'
                )]) {
                    sh '''
                    curl -u "$NEXUS_USER:$NEXUS_PASS" \
                      --upload-file build/${APP_NAME}-${VERSION}.zip \
                      ${NEXUS_URL}/repository/${NEXUS_REPO}/${APP_NAME}-${VERSION}.zip
                    '''
                }
            }
        }
    }

    post {
        success {
            echo "Build uploaded to Nexus successfully."
        }
        failure {
            echo "Build failed."
        }
    }
}