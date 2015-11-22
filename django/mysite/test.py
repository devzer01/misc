array = ["is", "are", "was", "the", "he", "she", "fox", "jumped"]
sentence = "He was walking down the road"
words = sentence.split(" ");
newarray = [];
for word in words:
    if word.lower() in array:
         newarray.append(word)
    for i in range(0, len(word), 1):
         newarray.append(word[i:i+1])

for word in newarray:
     print word


