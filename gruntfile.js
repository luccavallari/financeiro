"use strict";

module.exports = function(grunt) {
    
  //plugin para automatizar o carregamento dos Plugin 
  require("matchdep").filterDev("grunt-*").forEach(grunt.loadNpmTasks);

  //Configuracoes das tarefas
  grunt.initConfig({

    //verifi os arquivos caso algum mude executa a tarefa
    watch:{
        options:{
            nospawn: true,
            livereload: false,
        },
        html:{
            files:['application/views/**/*','assets/js/**/*']
        }
    },

    //syncroniza os navegadores (util para fazer teste de responsividade)
    browserSync: {
        options: {
          reloadDelay: 1000,
          ghostMode: {
              scroll: true,
              links: true,
              forms: true,
              clicks: true,
              location: true
          }
        },
        dev: {
            bsFiles: {
                src: [
                    'application/views/**/*',
                    'assets/js/**/*'
                ]
            },
            options: {
                watchTask: true,
                proxy: "localhost"
            }
        }
    },

    //realiza deploy
    /*rsync:{
        options: {
            args: ['--verbose'],
            compareMode: 'checksum',
            recursive: true,
            syncDest: true               
        },
        dist:{
            options:{
                src:"/var/www/html/locaweb-style/*",
                dest: '/var/www/html/v300',
                exclude: [
                          "analise",
                          "node_modules",
                          "gruntfile.js",
                          "package.json",
                          "user_guide",
                          "vendor",
                          'composer.json',
                          'composer.lock'
                          ]
            }
        },
        prod:{
            options:{
                ssh: true,
                src:"/var/www/html/v300/*",
                dest: '/home/ambientc/public_html/app-v300',
                host: 'ambientc@ambientclinic.com.br',
                delete: false 
            }
        }
    }*/


  });//fim configuracoes de tarefas

  //agenda as tarefas
  grunt.registerTask('default',[]);
  grunt.registerTask('dev',['browserSync','watch']);
  grunt.registerTask('deploy',['rsync']);
};