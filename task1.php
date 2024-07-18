<?php

/**
 * This script scans the /datafiles directory for files that have names consisting
 * of letters and numbers and end with the .ixt extension. The valid filenames
 * are then displayed in alphabetical order.
 */

function findIxtFiles($directory)
{
    // Regular expression pattern to match filenames consisting of letters and numbers
    $pattern = '/^[a-zA-Z0-9]+\.ixt$/';

    // Initialize an array to hold matching filenames
    $matchingFiles = [];

    // Open the directory
    if ($handle = opendir($directory)) {
        // Loop through the directory entries
        while (false !== ($entry = readdir($handle))) {
            // Check if the entry matches the pattern
            if (preg_match($pattern, $entry)) {
                // If it matches, add it to the array
                $matchingFiles[] = $entry;
            }
        }
        // Close the directory handle
        closedir($handle);
    } else {
        // If unable to open directory, throw an error
        throw new Exception("Unable to open directory: $directory");
    }

    // Sort the matching files alphabetically
    sort($matchingFiles);

    // Return the sorted array of matching filenames
    return $matchingFiles;
}

// Define the directory to search
$directoryPath = '/datafiles';

try {
    // Call the function to find .ixt files
    $ixtFiles = findIxtFiles($directoryPath);

    // Display the matching filenames
    if (!empty($ixtFiles)) {
        echo "Found .ixt files:\n";
        foreach ($ixtFiles as $file) {
            echo $file . "\n";
        }
    } else {
        echo "No .ixt files found in the directory.\n";
    }
} catch (Exception $e) {
    // Handle any exceptions that occur during file searching
    echo "Error: " . $e->getMessage() . "\n";
}

?>
