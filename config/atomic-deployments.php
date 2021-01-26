<?php

return [

    /**
     * Symbolic link to the current deployed build
     * This path should be used for schedules and setting your web root
     */
    'deployment-link' => env('ATM_DEPLOYMENT_LINK'),

    /**
     * The primary build folder
     * This folder is where all deployments ran and ultimately copied to a deployment directory
     */
    'build-path' => env('ATM_BUILD'),

    /**
     * Production build directory
     * Builds are copied here and linked for deployment
     * Ensure this directory has the required permissions to allow php and your webserver to run your application here
     */
    'deployments-path' => env('ATM_DEPLOYMENTS'),

    /**
     * Max number of build directories allowed
     * Once limit is hit, old deployments will be removed automatically after a successful build
     */
    'build-limit' => 5,

    /**
     * Migrate files|folders from the outgoing production build to your new release using a relative path and pattern
     * @see https://www.php.net/manual/en/function.glob.php
     */
    'migrate' => [
        'storage/framework/sessions/*',
    ],

    /**
     * Deployment class used.
     *
     * Add custom deployments by implementing @see \JTMcC\AtomicDeployments\Interfaces\DeploymentInterface
     * and adding your class to this config property
     */
    'deployment-class' => \JTMcC\AtomicDeployments\Services\Deployment::class,

    /**
     * Logic used when creating a deployment directory
     *
     * Default => git - uses hash for current HEAD
     * Options: [ git, datetime, rand ]
     *
     * If your build does not use git, use rand.
     */
    'directory-naming' => 'git'

];
