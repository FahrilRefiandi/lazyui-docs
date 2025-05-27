@aware(['tableName','localisationPath'])

<div x-data x-cloak x-show="reorderStatus" >
    <button
        x-on:click="reorderToggle"
        type="button"
        class="inline-flex justify-center items-center w-full md:w-auto px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:text-gray-500 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 active:bg-gray-50 active:text-gray-800 transition ease-in-out duration-150 dark:bg-gray-700 dark:text-white dark:border-gray-600 dark:hover:bg-gray-600"
    >
        <span x-cloak x-show="currentlyReorderingStatus">
         {{ __($localisationPath.'cancel') }}
        </span>

        <span x-cloak x-show="!currentlyReorderingStatus">
        {{ __($localisationPath.'Reorder') }}
        </span>
    </button>

    <div :class="{ 'inline d-inline' : currentlyReorderingStatus }" x-cloak x-show="currentlyReorderingStatus" >
        <button
            type="button"
            x-on:click="updateOrderedItems"
            class="inline-flex justify-center items-center w-full md:w-auto px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:text-gray-500 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 active:bg-gray-50 active:text-gray-800 transition ease-in-out duration-150 dark:bg-gray-700 dark:text-white dark:border-gray-600 dark:hover:bg-gray-600"
        >
            <span>
            {{ __($localisationPath.'save') }}
            </span>
        </button>
    </div>
</div>
