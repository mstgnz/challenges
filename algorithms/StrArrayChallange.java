import java.util.*; 
import java.io.*;

class Main {

  public static String ArrayChallenge(String[] strArr) {
    String text1 = "";
    String text2 = "";

    for(int i = 0; i < strArr[0].length(); i++){
      text1 = strArr[0].substring(0,i);
      text2 = strArr[0].substring(i, strArr[0].length());
      if(strArr[1].contains(text1) && strArr[1].contains(text2)){
        break;
      }
    }
    return text1 +","+ text2;
  }

  public static void main (String[] args) {  
    // keep this function call here     
    Scanner s = new Scanner(System.in);
    System.out.print(ArrayChallenge(s.nextLine())); 
  }

}

/*
Array Challenge
Have the function ArrayChallenge(strArr) read the array of strings stored in strArr, which will contain 2 elements: the first element will be a sequence of characters, and the second element will be a long string of comma-separated words, in alphabetical order, that represents a dictionary of some arbitrary length. For example: strArr can be: ["hellocat", "apple,bat,cat,goodbye,hello,yellow,why"]. Your goal is to determine if the first element in the input can be split into two words, where both words exist in the dictionary that is provided in the second input. In this example, the first element can be split into two words: hello and cat because both of those words are in the dictionary.

Your program should return the two words that exist in the dictionary separated by a comma. So for the example above, your program should return hello,cat. There will only be one correct way to split the first element of characters into two words. If there is no way to split string into two words that exist in the dictionary, return the string not possible. The first element itself will never exist in the dictionary as a real word.
Examples
Input: new String[] {"baseball", "a,all,b,ball,bas,base,cat,code,d,e,quit,z"}
Output: base,ball
*/
