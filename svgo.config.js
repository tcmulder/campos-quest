export default {
	plugins: [
		{
			name: 'removeDimensions', // Removes width/height, keeps viewBox
		},
		{
			name: 'preset-default',
			params: {
				overrides: {
					collapseGroups: false, // Do not collapse groups
					cleanupIDs: false, // Do not change or shorten ID names
				},
			},
		},
	],
};
