---
postTitle: The Algorithm of the ISBN
date: 2021-10-10
labels: ['javascript', 'algorithms']
description: 'Today I learned that the ISBN numbers on books have a built-in validation algorithm. Follow along here to see how I wrote my own JavaScript function that uses this algorithm and validates any ISBN argument given to it.'
---

Today I learned that the ISBN numbers on books have a built-in validation algorithm. Follow along here to see how I wrote my own JavaScript function that uses this algorithm and validates any ISBN argument given to it.

# Overview

International Standard Book Numbers (ISBN) started with 10 digits, but after 2007 were updated to 13 digits. Each system (ISBN-10 and ISBN-13) has its own algorithm for checking validity but are similar in their content and execution.

Each ISBN contains the following groups of numbers:

ISBN [ 978<sup>**1**</sup>-12<sup>**2**</sup>-3456<sup>**3**</sup>-789<sup>**4**</sup>-0<sup>**5**</sup> ]

1. Prefix: Currently either 978 or 979 (only used for ISBN-13).
2. Registration Group: Corresponds to a shared language or country/territory.
3. Registrant: Corresponds to the publisher of the book.
4. Publication: Corresponds to the specific book published.
5. Check Digit: *This is where the magic happens.*

Altogether, this includes a *lot* of numbers. For libraries and individuals that are manually inputting these numbers into their systems, it would be easy for them to miss just one or flip two adjacent digits. Luckily, there are systems in each type of ISBN to verify that the number input is indeed a valid ISBN.
 
# ISBN-10

`ISBN [ 039309670X ]`

1. Taking each digit (not including check digit), multiply the first by 10, the second by 9, the third by 8 and so on through the first 9 digits.  
`0 x 10 = 0`  
`3 x 9 = 27`  
`9 x 8 = 72`  
`3 x 7 = 21`  
`0 x 6 = 0`  
`9 x 5 = 45`  
`6 x 4 = 24`  
`7 x 3 = 21`  
`0 x 2 = 0`
2. Add each new sum together.  
`0 + 27 + 72 + 21 + 0 + 45 + 24 + 21 + 0 = 210`
3. Find the remainder after dividing that sum by 11 ([modulo operation](https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Operators/Remainder))  
`210 % 11 = 1`
4. Subtract that number from 11.  
`11 - 1 = 10`  
5. That number should be the check digit, or if 10, should be X (roman numeral 10).  
`10 = X`

Here is that entire sequence in JavaScript form:

```javascript
function isbn10IsValid(isbn) {
  // split isbn into an array
  let isbnArr = isbn.split('');
  // remove and remember the check digit
  let checkDigit = isbnArr.pop();
  //  multiply each digit by 10, 9, 8. . .
  for (let i = 0; i < isbnArr.length; i++) {
    isbnArr[i] = isbnArr[i] * (10 - i);
  }
  // find sum of all digits
  let sum = isbnArr.reduce((acc, curr) => {
    return acc + curr;
  });
  // find the remainder of sum mod 11
  let mod = sum % 11;
  // calculate the final check digit
  let checkDigitCalc = 11 - mod;
  // convert check digit to X if needed
  if (checkDigitCalc == 10) {
    checkDigitCalc = 'X';
  }
  // compare final check digits and return boolean
  if (checkDigitCalc == checkDigit) {
    return true
  }
  else {
    return false
  }
}
```

# ISBN-13

`ISBN [ 9780393096705 ]`

1. Taking each digit (not including check digit), multiply the first by 1 and the second by 3. Repeat this through the first 12 digits.  
`9 x 1 = 9`  
`7 x 3 = 21`  
`8 x 1 = 8`  
`0 x 3 = 0`  
`3 x 1 = 3`  
`9 x 3 = 27`  
`3 x 1 = 3`  
`0 x 3 = 0`  
`9 x 1 = 9`  
`6 x 3 = 18`  
`7 x 1 = 7`  
`0 x 3 = 0`
2. Add each new sum together.  
`9 + 21 + 8 + 0 + 3 + 27 + 3 + 0 + 9 + 18 + 7 + 0 = 105`
3. Find the remainder after dividing that sum by 10 ([modulo operation](https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Operators/Remainder))  
`105 % 10 = 5`
4. Subtract that number from 10. That number should be the check digit.  
`10 - 5 = 5`

Here is that entire sequence in JavaScript form:

```javascript
function isbn13IsValid(isbn) {
  // split isbn into an array
  let isbnArr = isbn.split('');
  // remove and remember the check digit
  let checkDigit = isbnArr.pop();
  //  multiply each digit by 1, 3 (repeat. . .)
  isbnArr = isbnArr.map((num, idx) => {
    if (idx % 2) {
      return num * 3;
    }
    else {
      return num * 1;
    }
  });
  // find sum of all digits
  let sum = isbnArr.reduce((acc, curr) => {
    return acc + curr;
  });
  // find the remainder of sum mod 10
  let mod = sum % 10;
  // calculate the final check digit
  let checkDigitCalc = 10 - mod;
  // compare final check digits and return boolean
  if (checkDigitCalc == checkDigit) {
    return true
  }
  else {
    return false
  }
}
```

# Conclusion

There you have it. Now you know that the barcodes on the back of all your favorite books have a built in algorithm to verify their validity. If you ever find yourself writing a bookkeeping app or library management tool, you can use this knowledge to help test your user input concerning book ISBNs. Or you could take out a calculator and impress your friends next time you're at a bookstore!
