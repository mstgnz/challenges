public class ArrayChallenge {

    public static String arrayChallenge(String[] strArr) {
        StringBuilder result = new StringBuilder();
        String[] arr1 = strArr[0].replace("[","").replace("]","").split(", ");
        String[] arr2 = strArr[1].replace("[","").replace("]","").split(", ");
        int length = Math.max(arr1.length, arr2.length);
        for(int i = 0; i < length; i++){
            if(i < arr1.length && i < arr2.length){
                result.append(Integer.parseInt(arr1[i]) + Integer.parseInt(arr2[i])).append("-");
            }else{
                if(i < arr1.length){
                    result.append(arr1[i]).append("-");
                }
                if(i < arr2.length){
                    result.append(arr2[i]).append("-");
                }
            }
        }

        return result.toString().substring(0, result.length() -1);
    }

    public static void main(String[] args) {
        String[] s = new String[] {"[5, 2, 3]", "[2, 2, 3, 10, 6]"};
        System.out.print(arrayChallenge(s));
    }
}


/*
Have the function ArrayChallenge(strArr) read the array of strings stored in strArr which will contain only two elements, both of which will represent an array of positive integers. For example: if strArr is ["[1, 2, 5, 6]", "[5, 2, 8, 11]"], then both elements in the input represent two integer arrays, and your goal for this challenge is to add the elements in corresponding locations from both arrays. For the example input, your program should do the following additions: [(1 + 5), (2 + 2), (5 + 8), (6 + 11)] which then equals [6, 4, 13, 17]. Your program should finally return this resulting array in a string format with each element separated by a hyphen: 6-4-13-17.

If the two arrays do not have the same amount of elements, then simply append the remaining elements onto the new array (example shown below). Both arrays will be in the format: [e1, e2, e3, ...] where at least one element will exist in each array.

Exaple : 
Input: new String[] {"[5, 2, 3]", "[2, 2, 3, 10, 6]"}
Output: 7-4-6-10-6
*/
