---
postTitle: Building a Simple JavaScript Blockchain
date: 2021-08-15 00:00:00 -5:00
history: [2021-08-15 00:00:00 -5:00]
labels: ['javascript', 'blockchain']
description: 'When I learn about a new technology, I like to dive in deep: reading articles and documentation, watching videos, and experimenting with code. Recently, I got into blockchain technology and after wrapping my head around the initial concept, I was inspired to create my own blockchain (albeit rudimentary).'
---

When I learn about a new technology, I like to dive in deep: reading articles and documentation, watching videos, and experimenting with code. Recently, I got into blockchain technology and after wrapping my head around the initial concept, I was inspired to create my own blockchain (albeit rudimentary).

If you are new to the blockchain, I highly recommend watching [this video](https://youtu.be/hYip_Vuv8J0) where the concept is explained in five levels of difficulty. Everyone should take away something new, whether a beginner or expert.

As for our introductory blockchain, we'll be using JavaScript as our programming language, and you should also be familiar working within the browser console. Let's get into it!

# Setup

There is one thing about blockchain that we'll be depending on other code for: the Secure Hashing Algorithm (SHA), specifically SHA-256.

In short, SHA-256 is a cryptographic "hashing" function that takes a string of any length and coverts it into a fixed-length 256 bit "hash value". It only works one way and is therefore secure for passwords or anything you don't want someone to guess or know.

SHA is a whole other subject we won't get into here, but you can find the code you need for this project [here](https://gist.github.com/andrewbruner/4eb2310078cc923c3bfa763ff6fb61c7).

In your project directory, create a three files:

- `index.html`
- `sha256.js`
- `blockchain.js` 

Copy the SHA code from the link above into the `sha256.js` file. You can now close this file. We're done with it.

In your `index.html` file create a basic HTML boilerplate with an empty body, or whatever you'd like to put in there. Inside the `<body>` tag (and preferably at the end) insert two `<script>` tags.

```html
...
  <body>

    <script src="sha256.js"></script>
    <script src="blockchain.js"></script>
  </body>
</html>
```

These tags insert the code you need for hashing your blockchain as well as the code we're about to write for the actual blockchain.

# The Block Object

Opening your `blockchain.js` file, we'll start things off by writing a function to create a object representing each block of our blockchain.

```javascript
function Block() {

}
```

Inside this function we'll initialize three properties:

- `previousHash`: The hash value of the previous block on the chain.
- `data`: The data in this block.
- `hash`: The calculated hash of this block. We'll use the code from the `sha256.js` file here.
    

The final code for this function should be as follows:

```javascript
function Block(previousHash, data) {
  this.previousHash = previousHash;
  this.data = data;
  this.hash = SHA256(JSON.stringify(this));
}
```

# The Blockchain Object

Now we'll get to the actual blockchain object. There are several variables and methods we'll code inside this function.

- `chain`{ .text-base .bg-gray-300 .p-1 .rounded .font-mono }: An array of block objects (the actual blockchain!)
- `add()`: This will add a block to the blockchain.
- `print()`: This will print a log of the entire blockchain to the console.

```javascript
function Blockchain() {
  // The blockchain
  let chain = [];

  // Add a block to the chain
  this.add = function() {

  }

  // Print the blockchain to the console
  this.print = function() {

  }
}
```

## Adding a Block

Inside the `add()` method, we'll add a parameter named `data`. This is the data inside the block we want added to the blockchain.

```javascript
  this.add = function(data) {

  }
```

We need this method to do three things:

- Find the hash of the previous block on the chain
- Create the new block we want added to the chain (with the previous hash included in it)
- Push that new block onto the chain array
    

To find the previous hash on the block we'll reference the `chain` and access the last block on it at index `[chain.length - 1]` and finally reference its `hash` property through dot notation.

```javascript
  this.add = function(data) {
    let previousHash = chain[chain.length - 1].hash;
  }
```

Using this `previousHash` value, we'll create a new block object essentially "chaining" it to the previous block and holding its own data from the `data` parameter.

```javascript
  this.add = function(data) {
    let previousHash = chain[chain.length - 1].hash;
    let block = new Block(previousHash, data);
  }
```

Finally, we'll take that `block` object and push it onto the `chain`, finishing our `add()` method's functionality!

```javascript
  this.add = function(data) {
    let previousHash = chain[chain.length - 1].hash;
    let block = new Block(previousHash, data);
    chain.push(block);
  }
```

### The Genesis Block

The funny thing about adding a block, is that you need to have previous blocks on the chain. Because of this, a blockchain usually is created with a first or "genesis" block that all other blocks lead back to. Because our `add()` method needs a `previousHash` that won't exist initially, we can hardcode the create of our own genesis block when the blockchain object is instantiated.

At the bottom of our `Blockchain()` function we'll add the following code:

```javascript
chain.push(new Block('0', 'Genesis Block'));
```

This will push our first block object onto the `chain` array with a `previousHash` of `'0'` and the `data` being simply `'Genesis Block'`. Now our first normal block we add will reference this genesis block's new hash and "chain" itself to it.

### Initial Usage

If we open our `index.html` file in a new browser window and open the developer console, we can finally add blocks to our blockchain!

```javascript
let blockchain = new Blockchain();
blockchain.add('hello world');
```

But how do we know if it worked? How can we see our hard work chaining these blocks together? We'll need another method for that...

## Printing the Blockchain

Inside our `print()` method, we only need one line of code, though it is a little complicated. We'll add it first and then I'll explain.

```javascript
  this.print = function() {
    console.log(JSON.stringify(chain, null, 2));
  }
```

The `console.log()` function call should be straightforward enough. It logs the argument to the console for us to see, but what exactly are we logging?

In essence, we are logging the `chain` array (the blockchain we want to see) to the console. We just need to modify how it is handled by the `console.log()` function.

`JSON` is an object built into JavaScript that helps parse or analyze JavaScript Object Notation (JSON). We are using its `stringify()` method to convert our `chain` "object" into a "string" that can be logged and easily read at once.

You can read more about this method and its parameters [here](https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/JSON/stringify) if you like or just use the adapted code above to nicely log our `chain` object as a string for us to enjoy.

# Usage

Finally, our simple JavaScript blockchain is complete!

Navigating back to the browser console, we can utilize our code in the following ways:

## 1) Instantiation

Create an instance of our blockchain object and initialize the Genesis Block to it.

```javascript
let blockchain = new Blockchain();
```

## 2) Adding a Block

Add any data we like to a new block and chain it to our immutable blockchain.

```javascript
blockchain.add('hello world');
blockchain.add(['hello', 'world']);
blockchain.add({ message: 'hello world'});
```

## 3) Printing our Blockchain

Log our blockchain array of block objects to the console.

```javascript
blockchain.print();

[
  {
    "previousHash": "0",
    "data": "Genesis Block",
    "hash": "6068c315b086271eee3bf312a4e2b3596f6e1f8b08f2bde0384b37d765f1d7c1"
  },
  {
    "previousHash": "6068c315b086271eee3bf312a4e2b3596f6e1f8b08f2bde0384b37d765f1d7c1",
    "data": "hello world",
    "hash": "7c0f4b696749784e1afcaa62f2476ae4c25a0960920cbf0122a41a989803baea"
  },
  {
    "previousHash": "7c0f4b696749784e1afcaa62f2476ae4c25a0960920cbf0122a41a989803baea",
    "data": [
      "hello",
      "world"
    ],
    "hash": "bb724ed2e9c6f7a3cf51459f06b570833b1bdaf30e51aab6e3d33859b1207d14"
  },
  {
    "previousHash": "bb724ed2e9c6f7a3cf51459f06b570833b1bdaf30e51aab6e3d33859b1207d14",
    "data": {
      "message": "hello world"
    },
    "hash": "252f30a80d37da4731bed4ad4b0fb900a197c061678c87a7a757d86dce35642b"
  }
]
```

# Conclusion

There you have it. I simple (very simple) implementation of blockchain technology. I hope you enjoyed this initial foray into the potential future of the world wide web!

```javascript
// Block Object
function Block(previousHash, data) {
  this.previousHash = previousHash;
  this.data = data;
  this.hash = SHA256(JSON.stringify(this));
}

// Blockchain Object
function Blockchain() {

  // Chain of all blocks
  let chain = [];

  // Add block to chain
  this.add = function(data) {
    let previousHash = chain[chain.length - 1].hash;
    let block = new Block(previousHash, data);
    chain.push(block);
  }

  // Print chain to console
  this.print = function() {
    console.log(JSON.stringify(chain, null, 2));
  }

  // Add Genesis Block
  chain.push(new Block('0', 'Genesis Block'));
}
```
