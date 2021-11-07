public class StringChallenge {

    public static String stringChallenge(String str) {
        String[] parseStr = str.split("");
        String newStr = "";
        boolean check = true;
        for(int i = 0; i < parseStr.length; i++){
            if(Character.isAlphabetic(str.charAt(i))){
                if(check){
                    newStr += parseStr[i].toLowerCase();
                }else{
                    newStr += parseStr[i].toUpperCase();
                }
            }else{
                check = false;
            }
        }

        return newStr;
    }

    public static void main(String[] args) {
        String str = "cats AND*Dogs-are Awesome";
        System.out.print(stringChallenge(str));
    }
}

/*
Have the function StringChallenge(str) take the str parameter being passed and return it in proper camel case format where the first letter of each word is capitalized (excluding the first letter). The string will only contain letters and some combination of delimiter punctuation characters separating each word.

For example: if str is "BOB loves-coding" then your program should return the string bobLovesCoding.

Example:
Input: "cats AND*Dogs-are Awesome"
Output: catsAndDogsAreAwesome
*/
