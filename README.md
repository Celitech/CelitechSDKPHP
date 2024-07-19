# Template repo for PHP SDK

This template helps developers get started with publishing the PHP SDK to Packagist package repository.

## Prerequisites

Before you can publish this SDK to packagist you need to:
1. Create an account on [Packagist](https://packagist.org/).
1. Set the following secrets in your repository:
    - `PACKAGIST_USERNAME`: Your packagist username.
    - `PACKAGIST_TOKEN`: Your packagist token. You can find it in [your packagist account settings](https://packagist.org/profile/).

## Contents

This repository contains the following:

- A `README` that contains the instructions

## Instructions

1. Create a new target PHP SDK Repo by clicking the **Use this template** button at the top of this repository.

1. If you already have a Control Repo:

    1. Update your `LIBLAB_GITHUB_TOKEN` actions secret to a new token that has access to all your existing SDK repos, as well as this new one.

    1. Update the [`githubRepoName` field in the `php` section](https://developers.liblab.com/cli/config-file-overview-language/#githubreponame) of your liblab config file to the name of your new repo.

1. Run the GitHub Action `Generate SDKs using liblab` in the Control Repo that builds the SDK, and raises a PR against this target SDK Repo. This will be triggered automatically when you commit and push the update to the liblab config file.

1. Review and merge the PR.

1. Create a new release here in the PHP SDK repo.

1. (First time only) Go to the [Packagist](https://packagist.org/) website and click the `Submit` button to submit your package. Enter in the URL of your GitHub repository and Packagist will automatically update your package when you push a new release. Subsequent releases will be automatically updated on Packagist.
