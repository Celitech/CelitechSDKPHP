# Contributing

Thanks for your interest in contributing to this SDK! This document provides guidelines for contributing to the project.

## Getting Started

### Prerequisites

- PHP 8.1+
- Composer

### Installation

Install the project dependencies:

```bash
composer install
```

### Testing

Run the test suite:

```bash
composer test
```

Or run PHPUnit directly:

```bash
./vendor/bin/phpunit
```

### Linting & Formatting

Fix code style issues:

```bash
composer pint
```

### Syntax Check

Check that every PHP file parses:

```bash
composer build
```

`composer build` walks the source tree with `find` and `xargs`, so on Windows run it from WSL or Git Bash.

### Static Analysis

Run static analysis:

```bash
composer analyze
```

## About Generated Code

**Important**: Most files in this SDK are automatically generated from the API definition. Direct modifications to generated files will be overwritten the next time the SDK is generated.

### Generated Files

The following directories contain generated code:
- `src/` - API client classes and types
- Most PHP files in the project

### Changing the Generated Code

If you want to change how the SDK is generated, the change belongs in the generator rather than in this repository. Open an issue describing the change you need, or raise it with the maintainers of this SDK.

## Making Changes

### Workflow

1. Create a new branch for your changes
2. Make your modifications
3. Run tests to ensure nothing breaks: `composer test`
4. Run formatting: `composer pint`
5. Run static analysis: `composer analyze`
6. Commit your changes with a clear commit message
7. Push your branch and create a pull request

### Commit Messages

Write clear, descriptive commit messages that explain what changed and why.

### Code Style

This project uses Laravel Pint for automated code formatting. Run `composer pint` before committing to ensure your code meets the project's style guidelines.

## License

By contributing to this project, you agree that your contributions will be licensed under the same license as the project.
