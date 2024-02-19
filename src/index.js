/**
 * WordPress dependencies
 */
import { registerBlockType } from '@wordpress/blocks';

/**
 * Blocks dependencies.
 */
import * as gdprLink from './blocks/link';

const registerBlock = (block) => {
	if (!block) return;
	const { name, settings } = block;
	registerBlockType(name, settings);
};

export const registerBlocks = () => {
	[
		gdprLink
	].forEach(registerBlock);
};

registerBlocks();
