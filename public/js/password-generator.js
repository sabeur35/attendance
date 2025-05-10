/**
 * Password Generator for Attendance System
 * Generates secure random passwords for user registration
 */

document.addEventListener('DOMContentLoaded', function() {
    // Generate a password on page load
    const initialPassword = generateSecurePassword();
    
    // Display the initial password
    const passwordDisplay = document.getElementById('password-display');
    if (passwordDisplay) {
        passwordDisplay.textContent = initialPassword;
    }
    
    // Get the password generator button
    const generatePasswordBtn = document.getElementById('generate-password-btn');
    
    if (generatePasswordBtn) {
        generatePasswordBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Generate a secure random password
            const password = generateSecurePassword();
            
            // Set the password in both password fields
            document.getElementById('password').value = password;
            document.getElementById('password-confirm').value = password;
            
            // Show the generated password in the password display area
            if (passwordDisplay) {
                passwordDisplay.textContent = password;
            }
        });
    }
    
    // Copy password button functionality
    const copyPasswordBtn = document.getElementById('copy-password-btn');
    if (copyPasswordBtn) {
        copyPasswordBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            const passwordDisplay = document.getElementById('password-display');
            if (passwordDisplay) {
                // Copy the password to clipboard
                navigator.clipboard.writeText(passwordDisplay.textContent)
                    .then(() => {
                        // Show success message
                        const copyFeedback = document.getElementById('copy-feedback');
                        if (copyFeedback) {
                            copyFeedback.textContent = 'Password copied!';
                            copyFeedback.classList.remove('d-none');
                            
                            // Hide the message after 2 seconds
                            setTimeout(() => {
                                copyFeedback.classList.add('d-none');
                            }, 2000);
                        }
                    })
                    .catch(err => {
                        console.error('Could not copy text: ', err);
                    });
            }
        });
    }
});

/**
 * Generates a secure random password
 * @param {number} length - Length of the password (default: 12)
 * @returns {string} - A secure random password
 */
function generateSecurePassword(length = 12) {
    // Character sets for password generation
    const uppercaseChars = 'ABCDEFGHJKLMNPQRSTUVWXYZ'; // Removed confusing characters like I and O
    const lowercaseChars = 'abcdefghijkmnopqrstuvwxyz'; // Removed confusing characters like l
    const numberChars = '23456789'; // Removed confusing characters like 0 and 1
    const specialChars = '!@#$%^&*_-+=';
    
    // Combine all character sets
    const allChars = uppercaseChars + lowercaseChars + numberChars + specialChars;
    
    // Ensure the password has at least one character from each set
    let password = 
        getRandomChar(uppercaseChars) +
        getRandomChar(lowercaseChars) +
        getRandomChar(numberChars) +
        getRandomChar(specialChars);
    
    // Fill the rest of the password with random characters
    for (let i = 4; i < length; i++) {
        password += getRandomChar(allChars);
    }
    
    // Shuffle the password to make it more random
    return shuffleString(password);
}

/**
 * Gets a random character from a string
 * @param {string} charSet - Set of characters to choose from
 * @returns {string} - A single random character
 */
function getRandomChar(charSet) {
    return charSet.charAt(Math.floor(Math.random() * charSet.length));
}

/**
 * Shuffles a string
 * @param {string} str - String to shuffle
 * @returns {string} - Shuffled string
 */
function shuffleString(str) {
    const array = str.split('');
    for (let i = array.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [array[i], array[j]] = [array[j], array[i]];
    }
    return array.join('');
}
