# Contributing to Alumni TGP Portal

First off, thank you for considering contributing to Alumni TGP Portal! It's people like you that make Alumni TGP Portal such a great tool.

## Code of Conduct

This project and everyone participating in it is governed by our Code of Conduct. By participating, you are expected to uphold this code.

## How Can I Contribute?

### Reporting Bugs

Before creating bug reports, please check the issue list as you might find out that you don't need to create one. When you are creating a bug report, please include as many details as possible:

* Use a clear and descriptive title
* Describe the exact steps which reproduce the problem
* Provide specific examples to demonstrate the steps
* Describe the behavior you observed after following the steps
* Explain which behavior you expected to see instead and why
* Include screenshots if possible
* Include your environment details (OS, browser version, etc.)

### Suggesting Enhancements

Enhancement suggestions are tracked as GitHub issues. When creating an enhancement suggestion, please include:

* Use a clear and descriptive title
* Provide a step-by-step description of the suggested enhancement
* Provide specific examples to demonstrate the steps
* Describe the current behavior and explain the behavior you expected to see
* Explain why this enhancement would be useful
* List some other applications where this enhancement exists

### Pull Requests

* Fill in the required template
* Do not include issue numbers in the PR title
* Follow the coding standards
* Include appropriate test cases
* Document new code
* End all files with a newline

## Development Process

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

### Setup Development Environment

```bash
# Clone your fork
git clone https://github.com/your-username/alumni-tgp.git

# Add upstream remote
git remote add upstream https://github.com/original/alumni-tgp.git

# Install dependencies
composer install
npm install

# Copy environment file
cp .env.example .env

# Generate application key
php -r "file_exists('.env') || copy('.env.example', '.env');"

# Configure your environment variables
# Edit .env file with your local settings

# Run migrations
php artisan migrate

# Seed the database
php artisan db:seed

# Start development server
php -S localhost:8000
```

## Coding Standards

### PHP

* Follow PSR-12 coding standard
* Use type hints where possible
* Write documentation for methods and classes
* Write unit tests for new features
* Keep methods small and focused
* Use meaningful variable names

### JavaScript

* Use ES6+ features
* Follow Airbnb JavaScript Style Guide
* Write JSDoc comments for functions
* Use meaningful variable names
* Write unit tests for new features

### CSS

* Follow BEM naming convention
* Use Tailwind CSS utility classes
* Keep selectors simple
* Maintain mobile-first approach

## Testing

* Write unit tests for new features
* Ensure all tests pass before submitting PR
* Include integration tests where necessary
* Test across different browsers

```bash
# Run PHP tests
composer test

# Run JavaScript tests
npm test

# Run all tests with coverage
composer test-coverage
npm run test:coverage
```

## Documentation

* Update README.md with details of changes to the interface
* Update API documentation if endpoints are modified
* Add JSDoc comments for new JavaScript functions
* Add PHPDoc blocks for new PHP methods
* Update changelog following semantic versioning

## Git Commit Messages

* Use the present tense ("Add feature" not "Added feature")
* Use the imperative mood ("Move cursor to..." not "Moves cursor to...")
* Limit the first line to 72 characters or less
* Reference issues and pull requests liberally after the first line
* Consider starting the commit message with an applicable emoji:
    * 🎨 `:art:` when improving the format/structure of the code
    * 🐎 `:racehorse:` when improving performance
    * 🚱 `:non-potable_water:` when plugging memory leaks
    * 📝 `:memo:` when writing docs
    * 🐛 `:bug:` when fixing a bug
    * 🔥 `:fire:` when removing code or files
    * 💚 `:green_heart:` when fixing the CI build
    * ✅ `:white_check_mark:` when adding tests
    * 🔒 `:lock:` when dealing with security
    * ⬆️ `:arrow_up:` when upgrading dependencies
    * ⬇️ `:arrow_down:` when downgrading dependencies

## Additional Notes

### Issue and Pull Request Labels

* `bug` - Something isn't working
* `enhancement` - New feature or request
* `documentation` - Improvements or additions to documentation
* `help wanted` - Extra attention is needed
* `good first issue` - Good for newcomers
* `invalid` - Something's wrong
* `question` - Further information is requested
* `wontfix` - This will not be worked on

## Recognition

Contributors who submit a PR that gets merged will be added to the Contributors list in the README.md file.

Thank you for your contributions! 🎉