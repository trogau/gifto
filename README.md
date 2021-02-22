# gifto
Simple gifting wishlist system for families

## Overview

Really simple PHP-based website to allow families to run their own wishlists (e.g., for Christmas gift givings). 

## Installation

1. Install the contents of html/ into any PHP-capable web root
2. Install the contents of phplib/ into some non-web-accessible area
3. Added phplib/ into PHP's include_path so the files in html/ can find it
4. Search the code for 'gifto.com.au' (where it was originally hosted) and replace with the correct URL

## WARNING

This was written many years ago and deployed on a password-protected site; it was hastily hacked together and uses no PHP frameworks that take care of all the painful input sanitisation stuff, instead relying on some very basic filtering. It may not be safe to leave online. 

## TODO

- Confirm the input sanitisation is safe
- Replace the database queries with parameterised ones
- Make easier to deploy (do something better with the phplib/ include)
- Replace references to gifto.com.au with some configurable string

