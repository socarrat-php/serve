<?php
/**
 * Plugin.php
 *
 * @author Romein van Buren
 * @license MIT
 */

namespace Socarrat\Core;

abstract class Plugin {
	/** The machine-readable name of the plugin. */
	protected readonly string $name;

	/**
	 * An array of machine-readable strings representing features the plugin provides.
	 *
	 * Example: `array("SeePosts", "CreatePosts", "EditPosts", "DeletePosts")`
	 *
	 * @var string[]
	 */
	protected array $features;

	/** Returns the plugin name. */
	final function getName() {
		return $this->name;
	}

	/** Returns the defined features for this plugin. */
	final function getAllFeatures() {
		return $this->features;
	}

	/**
	 * Initialise your plugin here. This function is called by App::registerPlugin().
	 *
	 * For instance, you could setup event listeners here.
	 */
	abstract function init();
}
