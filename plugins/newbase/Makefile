##
# Newbase Plugin for GLPI
# Makefile for plugin development and release
##

PLUGIN_NAME = newbase
PLUGIN_VERSION = $(shell cat VERSION)
GLPI_MIN = 10.0.0
GLPI_MAX = 11.0.99

# Directories
SRC_DIR = .
BUILD_DIR = dist
RELEASE_NAME = glpi-$(PLUGIN_NAME)-$(PLUGIN_VERSION)

.PHONY: help clean install build release cs-check cs-fix test

## help: Display this help message
help:
	@echo "Newbase Plugin - Available targets:"
	@echo "  make install    - Install composer dependencies"
	@echo "  make clean      - Clean build directory"
	@echo "  make build      - Build plugin archive"
	@echo "  make release    - Create release package"
	@echo "  make cs-check   - Check coding standards"
	@echo "  make cs-fix     - Fix coding standards"
	@echo "  make test       - Run tests"

## install: Install composer dependencies
install:
	@echo "Installing composer dependencies..."
	composer install --no-dev --optimize-autoloader

## clean: Clean build directory
clean:
	@echo "Cleaning build directory..."
	rm -rf $(BUILD_DIR)
	rm -rf vendor
	rm -f composer.lock

## build: Build plugin archive
build:
	@echo "Building $(PLUGIN_NAME) version $(PLUGIN_VERSION)..."
	@mkdir -p $(BUILD_DIR)
	@mkdir -p $(BUILD_DIR)/$(PLUGIN_NAME)
	
	@echo "Copying files..."
	@rsync -av --exclude='$(BUILD_DIR)' \
		--exclude='.git' \
		--exclude='.gitignore' \
		--exclude='vendor' \
		--exclude='composer.lock' \
		--exclude='node_modules' \
		--exclude='.vscode' \
		--exclude='.idea' \
		--exclude='*.log' \
		--exclude='tests' \
		$(SRC_DIR)/ $(BUILD_DIR)/$(PLUGIN_NAME)/
	
	@echo "Installing production dependencies..."
	cd $(BUILD_DIR)/$(PLUGIN_NAME) && composer install --no-dev --optimize-autoloader
	
	@echo "Creating archive..."
	cd $(BUILD_DIR) && tar czf $(RELEASE_NAME).tar.gz $(PLUGIN_NAME)
	@echo "✓ Archive created: $(BUILD_DIR)/$(RELEASE_NAME).tar.gz"

## release: Create release package
release: clean build
	@echo "✓ Release $(PLUGIN_VERSION) is ready in $(BUILD_DIR)/"

## cs-check: Check coding standards (PSR-12)
cs-check:
	@echo "Checking coding standards..."
	composer cs:check

## cs-fix: Fix coding standards
cs-fix:
	@echo "Fixing coding standards..."
	composer cs:fix

## test: Run tests
test:
	@echo "Running tests..."
	composer test

.DEFAULT_GOAL := help
